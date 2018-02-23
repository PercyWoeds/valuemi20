class AddClientIdToNotaCredits < ActiveRecord::Migration
  def change
    add_column :notacredits, :client_id, :float
  end
end
