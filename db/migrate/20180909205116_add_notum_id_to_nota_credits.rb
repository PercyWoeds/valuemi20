class AddNotumIdToNotaCredits < ActiveRecord::Migration
  def change
    add_column :notacredits, :notum_id, :integer
  end
end
