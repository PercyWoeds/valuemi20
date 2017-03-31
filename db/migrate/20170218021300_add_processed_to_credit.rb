class AddProcessedToCredit < ActiveRecord::Migration
  def change
    add_column :credits, :processed, :string
  end
end
